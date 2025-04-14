const fs = require('fs');
const path = require('path');
const https = require('https');
const Ajv = require('ajv');
const { execSync } = require('child_process');

// For schema validation
const ajv = new Ajv({strict: false});

// Read package.json for version
const packageJson = JSON.parse(fs.readFileSync(path.join(__dirname, '..', 'package.json'), 'utf8'));
const currentVersion = packageJson.version;

// Validate version format (x.x.x)
if (!/^\d+\.\d+\.\d+$/.test(currentVersion)) {
    console.error('Error: Version must be in the format x.x.x');
    process.exit(1);
}

// Read readme.txt for version requirements and changelog
const readmeContent = fs.readFileSync(path.join(__dirname, '..', 'readme.txt'), 'utf8');

// Extract version requirements from readme.txt
const requiresMatch = readmeContent.match(/Requires at least: ([\d.]+)/);
const testedMatch = readmeContent.match(/Tested up to: ([\d.]+)/);
const requiresPhpMatch = readmeContent.match(/Requires PHP: ([\d.]+)/);

// Validate we found all required matches
if (!requiresMatch || !testedMatch || !requiresPhpMatch) {
    console.error('Error: Could not find all required version information in readme.txt');
    console.error('Required: ' + (requiresMatch ? '✓' : '✗'));
    console.error('Tested: ' + (testedMatch ? '✓' : '✗'));
    console.error('PHP: ' + (requiresPhpMatch ? '✓' : '✗'));
    process.exit(1);
}

// Extract changelog section
const changelogMatch = readmeContent.match(/== Changelog ==\n\n([\s\S]*?)(?=\n\n==|$)/);
let changelogHtml = '';
if (changelogMatch) {
    changelogHtml = changelogMatch[1]
        .replace(/= (.*?) =\n/g, '<h4>$1</h4>\n')
        .replace(/\n/g, '<br />\n');
} else {
    console.error('Warning: Could not find changelog section in readme.txt');
}

// Read schema for validation
const schema = JSON.parse(fs.readFileSync(path.join(__dirname, 'info-json-schema.json'), 'utf8'));

// Pre-compile the validation function
const validate = ajv.compile(schema);

function fetchJson(url) {
    return new Promise((resolve, reject) => {
        console.log(`Fetching ${url}...`);
        const req = https.get(url, (res) => {
            if (res.statusCode !== 200) {
                reject(new Error(`HTTP ${res.statusCode}: ${res.statusMessage}`));
                return;
            }

            let data = '';
            res.on('data', (chunk) => data += chunk);
            res.on('end', () => {
                try {
                    const json = JSON.parse(data);
                    resolve(json);
                } catch (error) {
                    reject(new Error('Invalid JSON response: ' + error.message));
                }
            });
        });

        req.on('error', reject);
        req.setTimeout(10000, () => {
            req.destroy();
            reject(new Error('Request timed out'));
        });
    });
}

function formatDate(date) {
    // Format: YYYY-MM-DD HH:mm:ss GMT
    return date.toISOString().replace('T', ' ').replace(/\.\d{3}Z$/, ' GMT');
}

// Function to show git diff between two files
function showDiff(downloadedInfo, outputPath) {
    const tempPath = outputPath + '.old';
    fs.writeFileSync(tempPath, JSON.stringify(downloadedInfo, null, 2));
    try {
        const diff = execSync(`git diff --no-index "${tempPath}" "${outputPath}"`).toString();
        if (diff) {
            console.log('\nChanges made to info.json:');
            console.log(diff);
        } else {
            console.log('\nWarning: No changes detected in info.json');
            console.log('Did you remember to bump the plugin version in package.json?');
        }
    } catch (error) {
        // git diff returns exit code 1 if there are differences,
        // which causes execSync to throw
        if (error.stdout) {
            console.log('\nChanges made to info.json:');
            console.log(error.stdout.toString());
        }
    } finally {
        fs.unlinkSync(tempPath);
    }
}

async function createInfoJson() {
    try {
        console.log('Current version:', currentVersion);
        console.log('WordPress version requirements:');
        console.log('- Requires at least:', requiresMatch[1]);
        console.log('- Tested up to:', testedMatch[1]);
        console.log('- Requires PHP:', requiresPhpMatch[1]);

        // Fetch current info.json
        const info = await fetchJson('https://wpe-plugin-updates.wpengine.com/genesis-simple-menus/info.json');
        const originalInfo = JSON.parse(JSON.stringify(info)); // copy of info, not reference.
        console.log('Successfully fetched current info.json');

        // Update required fields
        info.version = currentVersion;
        info.download_link = info.download_link.replace(/genesis-simple-menus\.\d+\.\d+\.\d+\.zip/, `genesis-simple-menus.${currentVersion}.zip`);
        info.versions[currentVersion] = info.download_link;
        info.last_updated = formatDate(new Date());

        // Update version requirements
        info.requires = requiresMatch[1];
        info.tested = testedMatch[1];
        info.requires_php = requiresPhpMatch[1];

        // Update changelog
        info.sections.changelog = changelogHtml;

        // If missing, create fields required to pass validation.
        if (!info.sections.faq) {
            info.sections.faq = '';
        }

        if (!info.sections.screenshots) {
            info.sections.screenshots = '';
        }

		// Adjust other fields to meet validation requirements.
		if (!info.screenshots || Array.isArray(info.screenshots)) {
			info.screenshots = {};
		}

        // Create artifacts/wpe directory if it doesn't exist
        const buildDir = path.join(__dirname, '..', 'artifacts', 'wpe');
        if (!fs.existsSync(buildDir)) {
            fs.mkdirSync(buildDir, { recursive: true });
        }

        // Write the updated info.json
        const outputPath = path.join(buildDir, 'info.json');
        fs.writeFileSync(outputPath, JSON.stringify(info, null, 2));

        // Read back the file and validate it
        const writtenInfo = JSON.parse(fs.readFileSync(outputPath, 'utf8'));
        const isValid = validate(writtenInfo);
        if (!isValid) {
            console.error('Validation failed. The following errors were found:');
            validate.errors.forEach((error) => {
                console.error(`- ${error.instancePath}: ${error.message}`);
            });
            process.exit(1);
        }
        console.log('Successfully created info.json at', outputPath);
        console.log('New info.json passed validation');

        // Show git diff between downloaded and new JSON
        showDiff(originalInfo, outputPath);
    } catch (error) {
        console.error('Error creating info.json:', error.message);
        process.exit(1);
    }
}

createInfoJson();
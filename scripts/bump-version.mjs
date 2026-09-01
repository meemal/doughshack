/**
 * Increments the WordPress theme Version in sass/style.scss (source of truth).
 * Run before build/dist so style.css picks up the new header.
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, '..');
const scssPath = path.join(root, 'sass', 'style.scss');
const readmePath = path.join(root, 'readme.txt');

function bumpVersion(version) {
	const letterSuffix = version.match(/^(.+?)([a-z])$/i);
	if (letterSuffix) {
		const base = letterSuffix[1];
		const letter = letterSuffix[2].toLowerCase();
		if (letter === 'z') {
			return bumpPatchVersion(base) + 'a';
		}
		return base + String.fromCharCode(letter.charCodeAt(0) + 1);
	}

	return bumpPatchVersion(version);
}

function bumpPatchVersion(version) {
	const parts = version.split('.');
	if (parts.length < 3) {
		return version + '.1';
	}
	const patch = parseInt(parts[2], 10);
	parts[2] = String(Number.isNaN(patch) ? 1 : patch + 1);
	return parts.join('.');
}

let content = fs.readFileSync(scssPath, 'utf8');
const versionMatch = content.match(/^Version:\s*(.+)$/m);
if (!versionMatch) {
	console.error('Could not find Version in sass/style.scss');
	process.exit(1);
}

const current = versionMatch[1].trim();
const next = bumpVersion(current);

content = content.replace(/^Version:\s*.+$/m, `Version: ${next}`);
fs.writeFileSync(scssPath, content);

if (fs.existsSync(readmePath)) {
	let readme = fs.readFileSync(readmePath, 'utf8');
	if (/^Stable tag:\s*.+$/m.test(readme)) {
		readme = readme.replace(/^Stable tag:\s*.+$/m, `Stable tag: ${next}`);
		fs.writeFileSync(readmePath, readme);
	}
}

console.log(`Theme version: ${current} → ${next}`);

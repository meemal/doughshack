/**
 * Copies the theme into dist/thedoughshack/ for FTP upload (no zip).
 * Run after: npm run build
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, '..');
const destRoot = path.join(root, 'dist', 'thedoughshack');

/** Top-level names under the theme root to skip */
const DIR_EXCLUDE = new Set([
	'node_modules',
	'sass',
	'dist',
	'.git',
	'.cursor',
	'scripts',
	'.idea',
	'.vscode',
]);

/** Skip these file names anywhere */
const FILE_EXCLUDE = new Set(['.DS_Store', 'error_log', 'Thumbs.db']);

function skipFile(base) {
	if (FILE_EXCLUDE.has(base)) return true;
	if (base.endsWith('.css.map')) return true;
	if (base.endsWith('.zip')) return true;
	return false;
}

/** Skip these root-level files (dev-only; not needed on the server) */
const ROOT_FILE_EXCLUDE = new Set([
	'package.json',
	'package-lock.json',
	'.gitignore',
]);

function copyRecursive(src, dest) {
	const stat = fs.statSync(src);
	if (stat.isDirectory()) {
		fs.mkdirSync(dest, { recursive: true });
		for (const name of fs.readdirSync(src)) {
			if (DIR_EXCLUDE.has(name)) continue;
			const from = path.join(src, name);
			const to = path.join(dest, name);
			const base = path.basename(from);
			if (skipFile(base)) continue;
			if (src === root && ROOT_FILE_EXCLUDE.has(name)) continue;
			copyRecursive(from, to);
		}
	} else {
		const base = path.basename(src);
		if (skipFile(base)) return;
		fs.copyFileSync(src, dest);
	}
}

if (fs.existsSync(destRoot)) {
	fs.rmSync(destRoot, { recursive: true, force: true });
}

copyRecursive(root, destRoot);

console.log('');
console.log('FTP-ready theme folder:');
console.log('  ' + destRoot);
console.log('');
console.log('Upload the *contents* of that folder into wp-content/themes/thedoughshack/ on the server.');
console.log('(Zip is optional — use Finder / your FTP client on the folder as-is.)');
console.log('');

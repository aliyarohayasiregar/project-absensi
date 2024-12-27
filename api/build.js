import { execSync } from 'child_process';
import { copyFileSync } from 'fs';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const rootDir = join(__dirname, '..');

try {
    // Copy .env.vercel to .env
    console.log('Copying environment file...');
    copyFileSync(join(rootDir, '.env.vercel'), join(rootDir, '.env'));

    // Install dependencies
    console.log('Installing npm dependencies...');
    execSync('npm install', { 
        stdio: 'inherit',
        cwd: rootDir 
    });

    // Run build
    console.log('Running build...');
    execSync('npm run build', { 
        stdio: 'inherit',
        cwd: rootDir 
    });

    console.log('Build completed successfully!');
} catch (error) {
    console.error('Build failed:', error);
    process.exit(1);
} 
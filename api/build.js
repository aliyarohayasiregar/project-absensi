const { execSync } = require('child_process');
const fs = require('fs');

// Copy .env.vercel to .env
fs.copyFileSync('.env.vercel', '.env');

// Install dependencies
console.log('Installing npm dependencies...');
execSync('npm install', { stdio: 'inherit' });

// Run build
console.log('Running build...');
execSync('npm run build', { stdio: 'inherit' }); 
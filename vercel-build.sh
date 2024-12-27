#!/bin/bash
echo "Running Vercel build script..."
cp .env.vercel .env
npm install
npm run build 
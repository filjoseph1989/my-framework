# Using tailwind
 npm i -g postcss-cli
 npm install tailwindcss
 npm install @fullhuman/postcss-purgecss --save-dev
 npm install postcss-import
 npm install postcss-nested
 npm install webpack webpack-cli --save-dev
 npm install npm-watch

 postcss views/css/main.css -o output.css
 npx tailwind build output.css -o main.css
 npx tailwind init

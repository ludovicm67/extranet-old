.PHONY: format
format:
	node_modules/prettier/bin/prettier.js --write "application/**/*.php"
	node_modules/prettier/bin/prettier.js --write "public/index.php"

.PHONY: serve
serve:
	php -S localhost:8008 -t ./public/

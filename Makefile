.PHONY: format
format:
	node_modules/prettier/bin/prettier.js --write "application/**/*.php"
	node_modules/prettier/bin/prettier.js --write "index.php"

.PHONY: format
format:
	node_modules/prettier/bin/prettier.js --write "application/**/*.php"
	node_modules/prettier/bin/prettier.js --write "public/index.php"

.PHONY: serve
serve:
	@echo 'Homepage URL: http://localhost:8008'
	php -S 0.0.0.0:8008 -t ./public/

.PHONY: deploy
deploy:
	composer install

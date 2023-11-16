start:
	@if [ -z "$(port)" ]; then \
        php -S localhost:8080; \
    else \
        php -S localhost:$(port); \
	fi

migrate-up:
	@php database/migrateup.php

seed:
	@php database/seed.php

migrate-down:
	@php database/migratedown.php
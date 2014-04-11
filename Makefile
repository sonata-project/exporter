.PHONY: test test-all install update clean dev bower load assets

test:
	phpunit
	cd docs && sphinx-build -W -b html -d _build/doctrees . _build/html

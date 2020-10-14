const helpers = require('../src/web/js/helpers');

test('return true if character is allowed', () => {
	expect(helpers.isCharacterForbidden('a')).toBe(true);
});

test('return false if character is forbidden', () => {
	expect(helpers.isCharacterForbidden('*')).toBe(false);
});

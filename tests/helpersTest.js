import { containsForbiddenChar } from '../src/web/js/helpers.js';

describe('containsForbiddenChar()', () => {
	it('should return true if the string is valid', () => {
		expect(containsForbiddenChar('a')).toBe(false);
	});

	it('should return false if there is a forbidden character', () => {
		expect(containsForbiddenChar('*')).toBe(true);
	});
});

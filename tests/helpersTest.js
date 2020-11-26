import chai from 'chai';
import { containsForbiddenChar } from '../src/web/js/helpers.js';

describe('containsForbiddenChar()', () => {
	it('should return true if the string is valid', () => {
		chai.expect(containsForbiddenChar('a')).to.be.false;
	});

	it('should return false if there is a forbidden character', () => {
		chai.expect(containsForbiddenChar('*')).to.be.true;
	});
});

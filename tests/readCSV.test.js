const reader = require('../src/web/js/reader');
const fs = require('fs');

const testSplit = [['This\r\ntext'], ['This\ntext']];
const testFiles = [
	[
		'./tests/mock.good.csv',
		{ data: ['This', 'text'], isValid: true, problematic: [] },
	],
	[
		'./tests/mock.bad.csv',
		{
			data: ['&BAD!', 'text;test'],
			isValid: false,
			problematic: [
				{ index: 0, value: '&BAD!' },
				{ index: 1, value: 'text;test' },
			],
		},
	],
];

describe('the reader', () => {
	test.each(testSplit)('can split text on any line break', (sampleText) => {
		const expected = { data: ['This', 'text'], isValid: true, problematic: [] };
		const data = reader.splitTextOnLinebreak(sampleText);

		expect(data).toStrictEqual(expected);
	});

	test('correctly detects when the data has a problem', () => {
		const sampleText = 'This\r\n&BAD!';
		const expected = {
			data: ['This', '&BAD!'],
			isValid: false,
			problematic: [{ index: 1, value: '&BAD!' }],
		};
		const data = reader.splitTextOnLinebreak(sampleText);

		expect(data).toStrictEqual(expected);
	});

	test.each(testFiles)(
		'returns the expected array',
		async (input, expected) => {
			const file = new File([fs.readFileSync(input, 'utf8')], 'file.csv');
			const csvArray = await reader.readCSV(file);

			expect(csvArray).toStrictEqual(expected);
		}
	);
});

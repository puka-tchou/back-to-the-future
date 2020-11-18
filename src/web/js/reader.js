import { containsForbiddenChar } from './helpers';

export const readCSV = async (input) => {
	let text;
	try {
		text = await input.text();
	} catch (error) {
		console.log(error);
	}
	const csvArray = splitTextOnLinebreak(text);

	return csvArray;
};

export const splitTextOnLinebreak = (text) => {
	let isValid = true;
	let problematic = [];

	const data = text.split(/\r?\n/).filter((value, index, self) => {
		if (containsForbiddenChar(value)) {
			isValid = false;
			problematic.push({ index, value });
		}

		return self.indexOf(value) === index;
	});

	return {
		isValid,
		problematic,
		data,
	};
};

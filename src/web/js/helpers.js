export const isCharacterForbidden = (char) => {
	const regex = /[^A-Z\d]/;
	return regex.test(char);
};

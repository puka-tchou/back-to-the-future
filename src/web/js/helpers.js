export const isCharacterForbidden = (char) => {
	const regex = /^[A-Z\d]+$/i;
	return regex.test(char);
};

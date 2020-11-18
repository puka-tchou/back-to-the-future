export const containsForbiddenChar = (string) => {
	const regex = /^[A-Z\d]+$/i;
	return !regex.test(string);
};

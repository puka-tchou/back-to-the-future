export const containsForbiddenChar = (string: string): boolean => {
	const regex = /^[A-Z\d]+$/i;
	return !regex.test(string);
};

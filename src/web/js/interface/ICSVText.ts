export interface ICSVText {
	csv: string[];
	isValid: boolean;
	problematicLine: {
		index: number;
		value: string;
	};
}

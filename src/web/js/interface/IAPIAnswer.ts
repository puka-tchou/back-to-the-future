export interface IAPIAnswer {
	code: number;
	message: string;
	body: { [key: string]: BodyValue };
}

export interface BodyValue {
	code: number;
	message: string;
	body: BodyElement[];
}

export interface BodyElement {
	id: string;
	part_number: string;
	date_checked: string;
	state: string;
	parts_in_stock: string;
	parts_on_order: string;
	min_order: string;
	supplier: string;
}

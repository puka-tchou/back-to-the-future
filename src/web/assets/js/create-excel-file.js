import xl from 'excel4node';

export const createExcelFile = (stock) => {
  const workbook = new xl.Workbook();
  const worksheet = workbook.addWorkSheet('Sheet 1');
  const part = stock[0];

  worksheet.cell(1, 1).string('Date');
};

import zipcelx from 'zipcelx';

export const createExcelFile = (stock) => {
  const config = {
    filename: 'stock-report',
    sheet: {
      data: [],
    },
  };

  let offset = 0;
  const dates = new Set();
  const data = [
    // Next array holds the dates
    [
      { value: '', type: 'string' },
      { value: '', type: 'string' },
    ],
  ];

  // First iterate over the part-numbers
  for (const partNumber in stock) {
    if (stock.hasOwnProperty(partNumber)) {
      const records = stock[partNumber].body;

      // Isolate each supplier so we can assignate
      // each stock value to the correct line
      const suppliers = new Set();
      records.forEach((record) => {
        suppliers.add(record.supplier);
        dates.add(record.date_checked);
      });

      const suppliersArray = [...suppliers];
      records.forEach((record) => {
        // Index 0 is for the dates
        const line = suppliersArray.indexOf(record.supplier) + 1 + offset;
        if (data[line] === undefined) {
          data.push([]);
        }

        data[line].push({ value: record.parts_in_stock, type: 'number' });
      });

      // Finally, add the suppliers and the part-number
      // at the beginning of the data set
      suppliersArray.forEach((supplier, index) => {
        // Index 0 is for the dates
        data[index + 1 + offset].unshift(
          { value: partNumber, type: 'string' },
          { value: supplier, type: 'string' }
        );
      });

      offset += suppliersArray.length;
    }
  }

  dates.forEach((date) => {
    data[0].push({ value: date, type: 'string' });
  });

  config.sheet.data = data;
  console.log('👇 excel file structure is below');
  console.log(config);
  zipcelx(config);
};

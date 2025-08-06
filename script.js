function exportToExcel() {
  var table = document.getElementById("laporanTable");
  var workbook = XLSX.utils.table_to_book(table, { sheet: "Laporan" });
  XLSX.writeFile(workbook, "laporan_usaha.xlsx");
}

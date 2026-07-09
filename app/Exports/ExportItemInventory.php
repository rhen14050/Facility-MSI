<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
// use Maatwebsite\Excel\Concerns\WithDrawings;

class ExportItemInventory implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $itemDetails;
    protected $from;
    protected $to;

    function __construct($itemDetails, $from, $to)
    {
        $this->itemDetails = $itemDetails;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View {
        return view('exports.inventory', ['data' => $this->itemDetails]);
        // return view('exports.test');
	}

    public function title(): string
    {
        // return $this->sheet_title;
        return 'Inventory';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header setup (you already have this)
                $sheet->getStyle('A1:V2')->getFont()->setSize(12)->setBold(true);
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(50);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(7);
                $sheet->getColumnDimension('E')->setWidth(7);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(7);
                $sheet->getColumnDimension('H')->setWidth(10);
                $sheet->getColumnDimension('I')->setWidth(12);
                $sheet->getColumnDimension('J')->setWidth(12);
                $sheet->getColumnDimension('Q')->setWidth(12);
                $sheet->getColumnDimension('V')->setWidth(12);
                $sheet->getColumnDimension('K')->setWidth(15);
                $sheet->getColumnDimension('O')->setWidth(20);
                $sheet->getColumnDimension('R')->setWidth(15);

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:J1');
                $sheet->mergeCells('K1:M1');
                $sheet->mergeCells('N1:O1');
                $sheet->mergeCells('P1:Q1');
                $sheet->mergeCells('R1:T1');
                $sheet->mergeCells('U1:V1');


                $sheet->setCellValue('A1', 'Classification');
                $sheet->setCellValue('B1', 'Material Name');
                $sheet->setCellValue('C1', 'Part Code');
                $sheet->setCellValue('D1', 'Min');
                $sheet->setCellValue('E1', 'Max');
                $sheet->setCellValue('F1', 'Account Classification');
                $sheet->setCellValue('G1', 'U/M');
                $sheet->setCellValue('H1', 'Unit Price');
                $sheet->setCellValue('I1', 'BOH');
                $sheet->setCellValue('I2', 'Qty.');
                $sheet->setCellValue('J2', 'Amt.');
                $sheet->setCellValue('K1', 'IN');
                $sheet->setCellValue('K2', 'Date Received');
                $sheet->setCellValue('L2', 'Qty.');
                $sheet->setCellValue('M2', 'Amt.');
                $sheet->setCellValue('N1', 'Invoice');
                $sheet->setCellValue('N2', 'No.');
                $sheet->setCellValue('O2', 'Supplier');
                $sheet->setCellValue('P1', 'Total Inventory');
                $sheet->setCellValue('P2', 'Qty.');
                $sheet->setCellValue('Q2', 'Amt.');
                $sheet->setCellValue('R1', 'OUT');
                $sheet->setCellValue('R2', 'Date Withdrawed');
                $sheet->setCellValue('S2', 'Qty.');
                $sheet->setCellValue('T2', 'Amt.');
                $sheet->setCellValue('U1', 'EOH');
                $sheet->setCellValue('U2', 'Qty.');
                $sheet->setCellValue('V2', 'Amt.');



                // Styling header
                $sheet->getStyle('A1:V2')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFCCFFFF');
                $sheet->getStyle('A1:V2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:V2')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A1:V2')->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $row = 3;

                // Group items by category
                $grouped = $this->itemDetails->groupBy('category');

                foreach ($grouped as $category => $items) {
                        // Map category IDs to friendly names
                        switch ($category) {
                            case 1:
                                $groupName = 'ACU';
                                break;
                            case 2:
                                $groupName = 'ACU-F2';
                                break;
                            case 3:
                                $groupName = 'Air Compressor';
                                break;
                            case 4:
                                $groupName = 'Electrical Spare Parts';
                                break;
                            case 5:
                                $groupName = 'Bldg. Maintenance';
                                break;
                            default:
                                $groupName = 'Unknown';
                                break;
                        }

                        $startRow = $row; // remember start row for merge

                        foreach ($items as $item) {

                            $price = $item->currency == 3
                                ? $item->unit_price
                                : $item->converted_unit_price;

                            $bohQty = 0;

                            foreach ($item->item_transaction_details as $trans) {

                                if ($trans->form == 1) {

                                    // Receive
                                    if (!$trans->delivery_date) {
                                        continue;
                                    }

                                    $date = \Carbon\Carbon::parse($trans->delivery_date);

                                } elseif ($trans->form == 2) {

                                    // Ignore withdrawals that are not approved
                                    if ($trans->approval_status != 2) {
                                        continue;
                                    }

                                    $date = $trans->transaction_date
                                        ? \Carbon\Carbon::parse($trans->transaction_date)
                                        : \Carbon\Carbon::parse($trans->created_at);

                                } else {
                                    continue;
                                }

                                if ($date->lt($this->from)) {

                                    if ($trans->form == 1) {
                                        $bohQty += (float) $trans->input;
                                    } elseif ($trans->form == 2) {
                                        $bohQty -= (float) $trans->output;
                                    }
                                }
                            }

                            // Fill cells
                            $sheet->setCellValue("B{$row}", $item->item_name);
                            $sheet->setCellValue("C{$row}", $item->item_code);
                            // $sheet->setCellValue("D{$row}", ''); // empty

                            $sheet->setCellValue("D{$row}", $item->min_stock);
                            $sheet->setCellValue("E{$row}", $item->max_stock);
                            $sheet->setCellValue("F{$row}", 'MSI'); // account classification
                            $sheet->setCellValue("G{$row}", $item->item_uom);
                            $sheet->setCellValue("H{$row}", $price);
                            // $sheet->setCellValue("I{$row}", $bohQty);
                            $sheet->getStyle("H{$row}")
                                ->getNumberFormat()
                                ->setFormatCode('#,##0.00');
                            $sheet->setCellValue("I{$row}", $bohQty);

                            // $sheet->setCellValue("J{$row}", $bohAmt);
                            $sheet->setCellValue("J{$row}", "=H{$row}*I{$row}");

                            $sheet->getStyle("J{$row}")
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.00');
                            // $sheet->setCellValue("I{$row}", $bohQty);

                            $currentQty = $bohQty;
                            $transactions = [];

                            // foreach ($item->item_transaction_details as $trans) {

                            //     if ($trans->form == 1) {

                            //         if (!$trans->delivery_date)
                            //             continue;

                            //         $date = \Carbon\Carbon::parse($trans->delivery_date);

                            //         if (!$date->betweenIncluded($this->from,$this->to))
                            //             continue;

                            //         $transactions[] = [

                            //             'date'=>$date,

                            //             'type'=>'IN',

                            //             'qty'=>$trans->input,

                            //             'reference'=>$trans->reference_no,

                            //             'supplier'=>$trans->supplier_name

                            //         ];

                            //     }

                            //     elseif($trans->form==2 && $trans->approval_status==2){

                            //         if(!$trans->transaction_date)
                            //             continue;

                            //         $date=\Carbon\Carbon::parse($trans->transaction_date);

                            //         if(!$date->betweenIncluded($this->from,$this->to))
                            //             continue;

                            //         $transactions[]=[

                            //             'date'=>$date,

                            //             'type'=>'OUT',

                            //             'qty'=>$trans->output

                            //         ];

                            //     }

                            // }

                            foreach ($item->item_transaction_details as $trans) {

                            if ($trans->form == 1) {

                                if (!$trans->delivery_date)
                                    continue;

                                $date = \Carbon\Carbon::parse($trans->delivery_date);

                                if (!$date->betweenIncluded($this->from, $this->to))
                                    continue;

                                $transactions[] = [

                                    'date'=>$date,

                                    'type'=>'IN',

                                    'qty'=>$trans->input,

                                    'reference'=>$trans->reference_no,

                                    'supplier'=>$trans->supplier_name

                                ];

                            }

                            elseif($trans->form==2 && $trans->approval_status==2){

                                if(!$trans->transaction_date)
                                    continue;

                                $date= \Carbon\Carbon::parse($trans->transaction_date);

                                if(!$date->betweenIncluded($this->from,$this->to))
                                    continue;

                                $transactions[]=[

                                    'date'=>$date,

                                    'type'=>'OUT',

                                    'qty'=>$trans->output

                                ];

                            }

                        }

                            usort($transactions, function ($a, $b) {

                                // Compare by date first
                                if ($a['date']->equalTo($b['date'])) {

                                    // Same type, keep order
                                    if ($a['type'] == $b['type']) {
                                        return 0;
                                    }

                                    // OUT before IN on the same day
                                    return $a['type'] == 'OUT' ? -1 : 1;
                                }

                                return $a['date']->timestamp <=> $b['date']->timestamp;
                            });

                            // Count IN and OUT transactions
                            $inTransactions = array_values(array_filter($transactions, function ($t) {
                                return $t['type'] == 'IN';
                            }));

                            $outTransactions = array_values(array_filter($transactions, function ($t) {
                                return $t['type'] == 'OUT';
                            }));

                            $firstTransaction = true;

                            // ======================================================
                            // ONE IN + ONE OUT = ONE ROW
                            // ======================================================
                            // if (count($transactions) == 2 &&
                            //     count($inTransactions) == 1 &&
                            //     count($outTransactions) == 1) {

                            //     $in = $inTransactions[0];
                            //     $out = $outTransactions[0];

                            //     // BOH
                            //     $sheet->setCellValue("I{$row}", $bohQty);
                            //     $sheet->setCellValue("J{$row}", "=H{$row}*I{$row}");

                            //     // IN
                            //     $sheet->setCellValue("K{$row}", $in['date']->format('Y-m-d'));
                            //     $sheet->setCellValue("L{$row}", $in['qty']);
                            //     $sheet->setCellValue("M{$row}", "=H{$row}*L{$row}");
                            //     $sheet->setCellValue("N{$row}", $in['reference']);
                            //     $sheet->setCellValue("O{$row}", $in['supplier']);

                            //     // OUT
                            //     $sheet->setCellValue("R{$row}", $out['date']->format('Y-m-d'));
                            //     $sheet->setCellValue("S{$row}", $out['qty']);
                            //     $sheet->setCellValue("T{$row}", "=H{$row}*S{$row}");

                            //     // Compute
                            //     $totalInventory = $bohQty + $in['qty'];
                            //     $endingQty = $totalInventory - $out['qty'];

                            //     $sheet->setCellValue("P{$row}", $totalInventory);
                            //     $sheet->setCellValue("Q{$row}", "=P{$row}*H{$row}");
                            //     $sheet->setCellValue("U{$row}", $endingQty);
                            //     $sheet->setCellValue("V{$row}", "=U{$row}*H{$row}");
                            // }else{
                            //     foreach ($transactions as $index => $tran) {

                            //         // Move to a new row only for the 2nd transaction onwards
                            //         if ($index > 0) {

                            //             $row++;

                            //             // Copy item details
                            //             foreach (range('A', 'H') as $col) {
                            //                 $sheet->setCellValue(
                            //                     "{$col}{$row}",
                            //                     $sheet->getCell($col . ($row - 1))->getValue()
                            //                 );
                            //             }

                            //             // Optional: Copy row style
                            //             $sheet->duplicateStyle(
                            //                 $sheet->getStyle("A" . ($row - 1) . ":V" . ($row - 1)),
                            //                 "A{$row}:V{$row}"
                            //             );
                            //         }

                            //         // =========================
                            //         // BOH (First transaction only)
                            //         // =========================
                            //         if ($firstTransaction) {

                            //             $sheet->setCellValue("I{$row}", $bohQty);
                            //             $sheet->setCellValue("J{$row}", "=H{$row}*I{$row}");

                            //         } else {

                            //             $sheet->setCellValue("I{$row}", "");
                            //             $sheet->setCellValue("J{$row}", "");
                            //         }

                            //         // =========================
                            //         // RECEIVE
                            //         // =========================
                            //         if ($tran['type'] == "IN") {

                            //             $sheet->setCellValue("K{$row}", $tran['date']->format('Y-m-d'));
                            //             $sheet->setCellValue("L{$row}", $tran['qty']);
                            //             $sheet->setCellValue("M{$row}", "=H{$row}*L{$row}");
                            //             $sheet->setCellValue("N{$row}", $tran['reference']);
                            //             $sheet->setCellValue("O{$row}", $tran['supplier']);

                            //             // Clear OUT columns
                            //             $sheet->setCellValue("R{$row}", "");
                            //             $sheet->setCellValue("S{$row}", "");
                            //             $sheet->setCellValue("T{$row}", "");

                            //             // Total Inventory after receive
                            //             $currentQty += $tran['qty'];

                            //             $sheet->setCellValue("P{$row}", $currentQty);

                            //         }
                            //         // =========================
                            //         // WITHDRAW
                            //         // =========================
                            //         else {

                            //             // Clear IN columns
                            //             $sheet->setCellValue("K{$row}", "");
                            //             $sheet->setCellValue("L{$row}", "");
                            //             $sheet->setCellValue("M{$row}", "");
                            //             $sheet->setCellValue("N{$row}", "");
                            //             $sheet->setCellValue("O{$row}", "");

                            //             $sheet->setCellValue("R{$row}", $tran['date']->format('Y-m-d'));
                            //             $sheet->setCellValue("S{$row}", $tran['qty']);
                            //             $sheet->setCellValue("T{$row}", "=H{$row}*S{$row}");

                            //             // Total Inventory before withdrawal
                            //             $sheet->setCellValue("P{$row}", $currentQty);

                            //             // Compute EOH
                            //             $currentQty -= $tran['qty'];
                            //         }

                            //         // Amount
                            //         $sheet->setCellValue("Q{$row}", "=P{$row}*H{$row}");

                            //         // Ending Inventory
                            //         $sheet->setCellValue("U{$row}", $currentQty);
                            //         $sheet->setCellValue("V{$row}", "=U{$row}*H{$row}");

                            //         $firstTransaction = false;
                            //     }

                            // }

                            $firstTransaction = true;

                            $outIndex = 0;
                            $inIndex = 0;

                            while ($outIndex < count($outTransactions) || $inIndex < count($inTransactions)) {

                                // New row only after the first iteration
                                if (!$firstTransaction) {

                                    $row++;

                                    foreach (range('A', 'H') as $col) {
                                        $sheet->setCellValue(
                                            "{$col}{$row}",
                                            $sheet->getCell($col . ($row - 1))->getValue()
                                        );
                                    }

                                    $sheet->duplicateStyle(
                                        $sheet->getStyle("A" . ($row - 1) . ":V" . ($row - 1)),
                                        "A{$row}:V{$row}"
                                    );
                                }

                                // ===========================
                                // BOH (First row only)
                                // ===========================
                                if ($firstTransaction) {

                                    $sheet->setCellValue("I{$row}", $bohQty);
                                    $sheet->setCellValue("J{$row}", "=H{$row}*I{$row}");

                                } else {

                                    $sheet->setCellValue("I{$row}", "");
                                    $sheet->setCellValue("J{$row}", "");
                                }

                                // ==================================
                                // OUT TRANSACTION
                                // ==================================
                                if (isset($outTransactions[$outIndex])) {

                                    $out = $outTransactions[$outIndex];

                                    $sheet->setCellValue("R{$row}", $out['date']->format('Y-m-d'));
                                    $sheet->setCellValue("S{$row}", $out['qty']);
                                    $sheet->setCellValue("T{$row}", "=H{$row}*S{$row}");

                                    // Total Inventory BEFORE withdrawal
                                    $sheet->setCellValue("P{$row}", "=I{$row}+L{$row}");

                                    // Compute EOH after OUT
                                    $currentQty -= $out['qty'];

                                    $outIndex++;

                                } else {

                                    $sheet->setCellValue("R{$row}", "");
                                    $sheet->setCellValue("S{$row}", "");
                                    $sheet->setCellValue("T{$row}", "");
                                }

                                // ==================================
                                // IN TRANSACTION
                                // ==================================
                                if (isset($inTransactions[$inIndex])) {

                                    $in = $inTransactions[$inIndex];

                                    $sheet->setCellValue("K{$row}", $in['date']->format('Y-m-d'));
                                    $sheet->setCellValue("L{$row}", $in['qty']);
                                    $sheet->setCellValue("M{$row}", "=H{$row}*L{$row}");
                                    $sheet->setCellValue("N{$row}", $in['reference']);
                                    $sheet->setCellValue("O{$row}", $in['supplier']);

                                    // Receive AFTER withdrawal
                                    $currentQty += $in['qty'];

                                    $inIndex++;

                                } else {

                                    $sheet->setCellValue("K{$row}", "");
                                    $sheet->setCellValue("L{$row}", "");
                                    $sheet->setCellValue("M{$row}", "");
                                    $sheet->setCellValue("N{$row}", "");
                                    $sheet->setCellValue("O{$row}", "");
                                }

                                // ===========================
                                // Totals
                                // ===========================

                                $sheet->setCellValue("Q{$row}", "=P{$row}*H{$row}");

                                $sheet->setCellValue("U{$row}", $currentQty);
                                $sheet->setCellValue("V{$row}", "=U{$row}*H{$row}");

                                $firstTransaction = false;
                            }

                            // IF NO TRANSACTION
                            $sheet->setCellValue("P{$row}", "=I{$row}+L{$row}");
                            // Ending Amount
                            $sheet->setCellValue("Q{$row}", "=H{$row}*P{$row}");
                            // EOH Qty = BOH + IN - OUT

                            $sheet->setCellValue("U{$row}", "=I{$row}+L{$row}-S{$row}");

                            // EOH Amount = EOH Qty × Unit Price
                            $sheet->setCellValue("V{$row}", "=U{$row}*H{$row}");



                                // Optional: set borders
                                $sheet->getStyle("A{$row}:V{$row}")->getBorders()->getAllBorders()
                                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                                $row++;
                        }

                        // Merge the category column for all items in this group
                        if ($row - 1 > $startRow) {
                            $sheet->mergeCells("A{$startRow}:A" . ($row - 1));

                        }
                        // Set merged cell value
                        $sheet->setCellValue("A{$startRow}", $groupName);
                        // Center alignment for merged cell
                        $sheet->getStyle("A{$startRow}:A" . ($row - 1))
                            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }

                //    $endRow = $row; // last row of the current group
                        $sheet->getStyle("A{$row}:V{$row}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFCCFFFF');

                        $sheet->getStyle("A{$row}:V{$row}")->getFont()->setSize(16)->setBold(true);
                        $sheet->getStyle("A{$row}:V{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("A{$row}:V{$row}")->getAlignment()->setWrapText(true);
                        $sheet->getStyle("A{$row}:V{$row}")->getBorders()->getAllBorders()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue("B{$row}", 'TOTAL');
                        $sheet->setCellValue("I{$row}", "=SUM(I3:I" .($row - 1) . ")");
                        $sheet->setCellValue("J{$row}", "=SUM(J3:J" .($row - 1) . ")");
                        $sheet->setCellValue("L{$row}", "=SUM(L3:L" .($row - 1) . ")");
                        $sheet->setCellValue("M{$row}", "=SUM(M3:M" .($row - 1) . ")");
                        $sheet->setCellValue("P{$row}", "=SUM(P3:P" .($row - 1) . ")");
                        $sheet->setCellValue("Q{$row}", "=SUM(Q3:Q" .($row - 1) . ")");
                        $sheet->setCellValue("S{$row}", "=SUM(S3:S" .($row - 1) . ")");
                        $sheet->setCellValue("T{$row}", "=SUM(T3:T" .($row - 1) . ")");
                        $sheet->setCellValue("U{$row}", "=SUM(U3:U" .($row - 1) . ")");
                        $sheet->setCellValue("V{$row}", "=SUM(V3:V" .($row - 1) . ")");


                        $rowRow = $row + 2;

                        /*
                        |--------------------------------------------------------------------------
                        | Prepared By
                        |--------------------------------------------------------------------------
                        */
                        $sheet->mergeCells("A{$rowRow}:D{$rowRow}");
                        $sheet->setCellValue("A{$rowRow}", "Prepared by:");

                        $sheet->getStyle("A" . ($rowRow + 4) . ":P" . ($rowRow + 4))
                            ->getFont()
                            ->setBold(true)
                            ->setSize(12);

                        $sheet->getStyle("A" . ($rowRow) . ":P" . ($rowRow))
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);


                        $sheet->mergeCells("A" . ($rowRow + 2) . ":D" . ($rowRow + 2));
                        $sheet->setCellValue("A" . ($rowRow + 2), "_________________________");

                        $sheet->mergeCells("A" . ($rowRow + 3) . ":D" . ($rowRow + 3));
                        $sheet->setCellValue("A" . ($rowRow + 3), "Myla Jean Magdasoc");

                        $sheet->mergeCells("A" . ($rowRow + 4) . ":D" . ($rowRow + 4));
                        $sheet->setCellValue("A" . ($rowRow + 4), "Facility Clerk");

                        $sheet->getStyle("A{$rowRow}:D" . ($rowRow + 4))
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                             /*
                        |--------------------------------------------------------------------------
                        | Checked By
                        |--------------------------------------------------------------------------
                        */
                        $sheet->mergeCells("F{$rowRow}:I{$rowRow}");
                        $sheet->setCellValue("F{$rowRow}", "Checked by:");

                        $sheet->mergeCells("F" . ($rowRow + 2) . ":I" . ($rowRow + 2));
                        $sheet->setCellValue("F" . ($rowRow + 2), "_________________________");

                        $sheet->mergeCells("F" . ($rowRow + 3) . ":I" . ($rowRow + 3));
                        $sheet->setCellValue("F" . ($rowRow + 3), "Mannix Daryl D. Tayag");

                        $sheet->mergeCells("F" . ($rowRow + 4) . ":I" . ($rowRow + 4));
                        $sheet->setCellValue("F" . ($rowRow + 4), "Sr. Supervisor");

                        $sheet->getStyle("F{$rowRow}:I" . ($rowRow + 4))
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        /*
                        |--------------------------------------------------------------------------
                        | Approved By
                        |--------------------------------------------------------------------------
                        */
                        $sheet->mergeCells("K{$rowRow}:N{$rowRow}");

                        $sheet->mergeCells("K" . ($rowRow + 2) . ":N" . ($rowRow + 2));
                        $sheet->setCellValue("K" . ($rowRow + 2), "_________________________");

                        $sheet->mergeCells("K" . ($rowRow + 3) . ":N" . ($rowRow + 3));
                        $sheet->setCellValue("K" . ($rowRow + 3), "Jeffrey D. Farcon");

                        $sheet->mergeCells("K" . ($rowRow + 4) . ":N" . ($rowRow + 4));
                        $sheet->setCellValue("K" . ($rowRow + 4), "Facility Asst. Manager");

                        $sheet->getStyle("K{$rowRow}:N" . ($rowRow + 4))
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                        /*
                        |--------------------------------------------------------------------------
                        | Final Approver
                        |--------------------------------------------------------------------------
                        */

                        $sheet->mergeCells("P{$rowRow}:S{$rowRow}");

                        $sheet->mergeCells("P" . ($rowRow + 2) . ":S" . ($rowRow + 2));
                        $sheet->setCellValue("P" . ($rowRow + 2), "_________________________");

                        $sheet->mergeCells("P" . ($rowRow + 3) . ":S" . ($rowRow + 3));
                        $sheet->setCellValue("P" . ($rowRow + 3), "Evangeline S. Molina");

                        $sheet->mergeCells("P" . ($rowRow + 4) . ":S" . ($rowRow + 4));
                        $sheet->setCellValue("P" . ($rowRow + 4), "Asst. Vice President");

                        $sheet->getStyle("P{$rowRow}:S" . ($rowRow + 4))
                            ->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            },
        ];
    }
}

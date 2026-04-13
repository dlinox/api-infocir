<?php

namespace App\Common\Helpers;

use Mpdf\Mpdf;

class PdfHelper
{
    const A4 = 'a4';
    const A5 = 'a5';
    const TICKET = 'ticket';

    private static array $formats = [
        self::A4 => [
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ],
        self::A5 => [
            'format' => 'A5',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 8,
        ],
        self::TICKET => [
            'format' => [80, 200],
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ],
    ];

    public static function create(string $format = self::A4, array $config = []): Mpdf
    {
        $base = [
            'mode' => 'utf-8',
            'tempDir' => storage_path('app/mpdf'),
        ];

        $formatConfig = self::$formats[$format] ?? self::$formats[self::A4];

        return new Mpdf(array_merge($base, $formatConfig, $config));
    }

    public static function createFromHtml(string $html, string $format = self::A4, array $config = [], ?string $headerHtml = null, ?string $footerHtml = null): Mpdf
    {
        $mpdf = self::create($format, $config);

        if ($headerHtml) {
            $mpdf->SetHTMLHeader($headerHtml);
        }

        if ($footerHtml) {
            $mpdf->SetHTMLFooter($footerHtml);
        }

        $mpdf->WriteHTML($html);

        return $mpdf;
    }

    public static function saveToStorage(Mpdf $mpdf, string $disk, ?string $filename = null): string
    {
        $filename = $filename ?? FileHelper::generateUniqueFilename('document', 'pdf');
        FileHelper::saveFile($mpdf->Output('', 'S'), $filename, $disk);

        return FileHelper::getFileUrl($disk, $filename);
    }

    public static function download(Mpdf $mpdf, string $filename = 'document.pdf')
    {
        return self::pdfResponse($mpdf, $filename, 'attachment');
    }

    public static function inline(Mpdf $mpdf, string $filename = 'document.pdf')
    {
        return self::pdfResponse($mpdf, $filename, 'inline');
    }

    private static function pdfResponse(Mpdf $mpdf, string $filename, string $disposition)
    {
        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
        ]);
    }
}

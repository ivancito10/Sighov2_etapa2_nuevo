<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class FtpSaveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pdfBase64;

    protected $filePath;

    protected $nombre_pdf;

    /**
     * Create a new job instance.
     */
    public function __construct($pdfContent, $filePath, $nombre_pdf)
    {
        // Convertimos el binario a texto Base64 para que Redis no falle
        $this->pdfBase64 = base64_encode($pdfContent);
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        try {
            // Decodificamos el texto para volver a tener el binario del PDF
            $pdfBinario = base64_decode($this->pdfBase64);

            $success = Storage::disk('ftp')->put($this->filePath, $pdfBinario);

            if (! $success) {
                throw new \Exception("Error al escribir en FTP: {$this->filePath}");
            }
            echo ' PDF subido desde Base64 al FTP: ' . $this->filePath . "\n";
            Log::info(' PDF subido desde Base64 al FTP: ' . $this->filePath);
        } catch (\Exception $e) {
            Log::error(' Error en Job FTP: ' . $e->getMessage());
            throw $e;
        }
    }
}

<?php

namespace SamDark\Sitemap\Writer;

/**
 * Flushes buffer into file with incremental deflating data, available in PHP 7.0+
 */
class DeflateWriter implements WriterInterface
{
    /**
     * @var null|resource for target file
     */
    private $file;

    /**
     * @var false|resource for writable incremental deflate context
     */
    private $deflateContext;

    /**
     * @param string $filename target file
     */
    public function __construct(string $filename)
    {
        $this->file = fopen($filename, 'ab');
        $this->deflateContext = deflate_init(ZLIB_ENCODING_GZIP);
    }

    /**
     * Deflate data in a deflate context and write it to the target file
     *
     * @param string $data
     * @param int $flushMode zlib flush mode to use for writing
     */
    private function write($data, $flushMode): void
    {
        \assert($this->file !== null);

        if($this->deflateContext === false) {
            throw new \RuntimeException("DeflateContext was false.");
        }
        $compressedChunk = deflate_add($this->deflateContext, $data, $flushMode);
        fwrite($this->file, $compressedChunk);
    }

    /**
     * Store data in a deflate stream
     *
     * @param string $data
     */
    public function append($data): void
    {
        $this->write($data, ZLIB_NO_FLUSH);
    }

    /**
     * Make sure all data was written
     */
    public function finish(): void
    {
        $this->write('', ZLIB_FINISH);

        $this->file = null;
        $this->deflateContext = false;
    }
}

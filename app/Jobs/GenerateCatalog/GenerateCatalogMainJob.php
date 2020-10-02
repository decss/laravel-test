<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCatalogMainJob extends AbstractJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->debug('START ::');

        // Кегируем продукты
        GenerateCatalogCacheJob::dispatchNow();

        // Создаем цепочку заданий для формирования файлов с ценами
        $chainPrices = $this->getChainPrices();

        // Основные задачи
        $chainMain = [
            new GenerateCategoriesJob(),    // Категории
            new GenerateDeliveriesJob(),    // Способы лоставки
            new GeneratePointsJob(),        // Пункты выдачи
        ];

        // Последние задачи
        $chainLast = [
            new ArchiveUploadsJob,
            new SendPriceRequestJob,
        ];

        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        GenerateGoodsFileJob::withChain($chain)->dispatch();
        // GenerateGoodsFileJob::dispatch()->chain($chain);

        $this->debug('FINISH ::');
    }

    /**
     * Формирование цепочек подзадач для генерации файлов с ценами
     * @return array
     */
    private function getChainPrices()
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}

<?php

namespace App\Nova\Cards\ZTestingDemoCards;

use Abordage\TableCard\TableCard;

class MyTableCard extends TableCard
{
    /**
     * Name of the card (optional, remove if not needed)
     */
    public string $title = 'Z Testing Demo Cards/ My Table Card';

    /**
     * The width of the card (1/2, 1/3, 1/4 or full).
     */
    public $width = '1/3';

    /**
     * Array of table rows
     *
     * Required keys: title, viewUrl
     * Optional keys: subtitle, editUrl
     */
    public function rows(): array
    {
        $rows = [];

        /** for example */
        $models = \App\Models\User::limit(5)->get();
        foreach ($models as $model) {
            $rows[] = [
                'title' => $model->name,
                'subtitle' => $model->email,
                'viewUrl' => $this->getResourceUrl($model),
                'editUrl' => $this->getResourceUrl($model) . '/edit',
            ];
        }

        return $rows;
    }
}

<?php


namespace Codilar\PdfTemplate\Model\Order\Pdf\Invoice\DataProvider;


use Codilar\PdfTemplate\Model\Order\Pdf\DataProviderInterface;

class Items implements DataProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getData(array $data = []): array
    {
        /** @var \Magento\Sales\Model\Order\Invoice $model */
        $model = $data['model'];
        $items = [];
        /** @var \Magento\Sales\Model\Order\Invoice\Item $item */
        foreach ($model->getAllItems() as $item) {
            if ($item->getOrderItem()->getParentItemId()) continue;
            $itemData = $item->getData();
            $itemData['model'] = $item;

            $options = [];
            foreach ($this->getItemOptions($item) as $option) {
                if (is_array($option['value'])) {
                    $values = $option['value'];
                } else {
                    $values = explode(',', $option['value']);
                }
                $values = array_map(function ($option) {
                    return trim($option);
                }, $values);
                $options[] = [
                    'label' => $option['label'],
                    'values' => implode(', ', $values)
                ];
            }
            $itemData['additional_options'] = $options;

            $items[] = $itemData;
        }
        return $items;
    }

    /**
     * Get item options.
     *
     * @param \Magento\Sales\Model\Order\Invoice\Item $item
     * @return array
     */
    protected function getItemOptions($item)
    {
        $result = [];
        $options = $item->getOrderItem()->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
            if (isset($options['bundle_options'])) {
                $bundleOptions = [];
                foreach ($options['bundle_options'] as $option) {
                    $optionValue = [];
                    foreach ($option['value'] as $value) {
                        $optionValue[] = sprintf(
                            '%s X %s (%s%s)',
                            $value['qty'],
                            $value['title'],
                            $item->getOrderItem()->getOrder()->getOrderCurrencyCode(),
                            $value['price']
                        );
                    }
                    $option['value'] = $optionValue;
                    $bundleOptions[] = $option;
                }
                $result = array_merge($result, $bundleOptions);
            }
        }
        return $result;
    }
}

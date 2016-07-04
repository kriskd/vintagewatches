<?php

class ShippingHelper extends AppHelper {

    /**
     * Returns a comma separated strings of zones for a shipping options.
     *
     * @param array $shippings An array of shipping options
     * @return string
     */
    public function getZones($shipping) {
        $zoneNames = Hash::extract($shipping, 'Zone.{n}.name');

        return implode(',', $zoneNames);
    }
}

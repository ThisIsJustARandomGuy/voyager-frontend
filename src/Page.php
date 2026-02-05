<?php

namespace Pvtl\VoyagerFrontend;


class Page extends \Pvtl\VoyagerPages\Page
{
    /**
     * Get the indexed data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }
}

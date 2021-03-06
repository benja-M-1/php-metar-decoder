<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for icao section
 */
class IcaoChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return '#^([A-Z0-9]{4}) #';
    }

    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);

        // throw error if nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'ICAO code not found', $this);
        }

        // retrieve found params
        $result = array(
            'icao' => $found[1],
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}

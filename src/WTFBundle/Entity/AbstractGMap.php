<?php
/**
 * Created by PhpStorm.
 * User: bibouille
 * Date: 24/11/17
 */
namespace WTFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test\TestBundle\Entity\AbstractGMap
 *
 * @author Sullivan SENECHAL
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractGMap
{
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    protected $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    protected $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    protected $pays;

    /**
     * @var float     Latitude of the position
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    protected $lat;

    /**
     * @var float     Longitude of the position
     *
     * @ORM\Column(name="lng", type="float", nullable=true)
     */
    protected $lng;

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    public function getPays()
    {
        return $this->pays;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        if (is_string($lat)) {
            $lat = floatval($lat);
        }
        $this->lat = $lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        if (is_string($lng)) {
            $lng = floatval($lng);
        }
        $this->lng = $lng;
    }
}

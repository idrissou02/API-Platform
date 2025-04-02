<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // Ajout de l'import manquant

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 * @ApiResource(
 *      itemOperations={
 *          "get_simple"={
 *              "method"="GET",
 *              "path"="/genre/{id}/simple",
 *              "normalization_context"={"groups"={"listGenreSimple"}}
 *          },
 *          "get_full"={
 *              "method"="GET",
 *              "path"="/genre/{id}/full",
 *              "normalization_context"={"groups"={"listGenreFull"}}
 *          }
 *      },
 *      collectionOperations={"get"}
 * )
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Il existe déjà un genre avec ce libellé {{ value }}, veuillez saisir un autre libellé"
 * )
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listGenreSimple","listGenreFull"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"listGenreSimple","listGenreFull"})
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     minMessage="Le libellé doit faire au moins {{ limit }} caractères",
     *     maxMessage="Le libellé doit faire au plus {{ limit }} caractères"
     * )
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="genre")
     * @Groups({"listGenreFull"})
     */
    private $editeur;

    public function __construct()
    {
        $this->editeur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getEditeur(): Collection
    {
        return $this->editeur;
    }

    public function addEditeur(Livre $editeur): self
    {
        if (!$this->editeur->contains($editeur)) {
            $this->editeur[] = $editeur;
            $editeur->setGenre($this);
        }

        return $this;
    }

    public function removeEditeur(Livre $editeur): self
    {
        if ($this->editeur->removeElement($editeur)) {
            // set the owning side to null (unless already changed)
            if ($editeur->getGenre() === $this) {
                $editeur->setGenre(null);
            }
        }

        return $this;
    }
}

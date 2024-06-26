<?php 
namespace App\Entity;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 */
class Category {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @ORM\JoinColumn(nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="string")
     * @ORM\JoinColumn(nullable=true)
     */
    private $image_url;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="categories")
     */
    private $articles;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct(){
        $this->created_at = new \DateTime();
        $this->articles = new ArrayCollection();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self
    {
        $this->name = $name;
        $slug = (new Slugify())->slugify($this->name);
        $this->setSlug($slug);

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of image_url
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * Set the value of image_url
     */
    public function setImageUrl($image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    /**
     * Get the value of articles
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set the value of articles
     */
    public function addArticle(Article $article): self
    {
        if(!$this->articles->contains($article)){
            $this->articles->add($article);
            if(!$article->getCategories()->contains($this)){
                $article->addCategory($this);
            }
        }

        return $this;

    }
    public function removeArticle(Article $article): self
    {
        if($this->articles->removeElement($article)){
            if($article->getCategories()->contains($this)){
                $article->removeCategory($this);
            }
        }

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
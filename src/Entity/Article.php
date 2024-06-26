<?php 
namespace App\Entity;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="articles")
 */
class Article{

     /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @ORM\JoinColumn(nullable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string")
     */
    private $image_url;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="articles")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct(){
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->created_at = new \DateTime();
    }
    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle($title): self
    {
        $this->title = $title;
        $slug = (new Slugify())->slugify($this->title);
        $this->setSlug($slug);

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     */
    public function setContent($content): self
    {
        $this->content = $content;

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
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     */
    public function setAuthor($author): self
    {
        $this->author = $author;

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

    public function addCategory(Category $category): self
    {
        if(!$this->categories->contains($category)){
            $this->categories->add($category);
            $category->addArticle($this);
        }

        return $this;
    }
    public function getCategories()
    {
        return $this->categories;
    }
    public function removeCategory(Category $category): self
    {
        if($this->categories->removeElement($category)){

            if($category->getArticles()->contains($this)){
                $category->removeArticle($this);
            }
        }

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

    /**
     * Get the value of comments
     */
    public function getComments()
    {
        return $this->comments;
    }
    public function addComment(Comment $comment): self
    {
        if(!$this->comments->contains($comment)){
            $this->comments->add($comment);
            $comment->addArticle($this);
        }

        return $this;
    }
    public function removeComment(Comment $comment): self
    {
        if($this->comments->removeElement($comment)){
            if($comment->getArticle() === $this){
                $comment->removeArticle();
            }
        }

        return $this;
    }

}

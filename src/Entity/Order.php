<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\OrderPost;
use App\Controller\OrdersDriver;

/**
 * @ApiResource(collectionOperations={
 *     "get",
 *     "post"={
 *         "method"="POST",
 *         "path"="/orders.{_format}",
 *         "controller"=OrderPost::class,
 *     },
 *     "by_driver_and_date"={
 *         "method"="GET",
 *         "path"="/orders/driver/{driver}/{date}",
 *         "controller"=OrdersDriver::class,
 *         "swagger_context" = {
 *                  "description"="Retrieves the collection of Order by a driver for a specific date.",
 *                  "parameters" = {
 *                      {
 *                          "name" = "driver",
 *                          "required" = true,
 *                          "type" = "string",
 *                          "in" = "path",
 *                          "description" = "Driver id"
 *                      },
 *                      {
 *                          "name" = "date",
 *                          "required" = true,
 *                          "type" = "string",
 *                          "in" = "path",
 *                          "description" = "Date of orders"
 *                      }
 *                  }
 *          }
 *     }
 * })
 *
 * @ApiFilter(DateFilter::class, properties={"deliveryDate": DateFilter::EXCLUDE_NULL})
 * @ApiFilter(SearchFilter::class, properties={"driver": "exact"})
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string The name of the person to whom the order will be delivered
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Name1"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var string The lastname of the person to whom the order will be delivered
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="Lastname1"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * @var string The email of the person to whom the order will be delivered
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="email@email.com"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Email
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @var string The phone of the person to whom the order will be delivered
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="622631698"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @var date Delviery date of the order
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "example"="2018/12/12"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(type="date")
     */
    private $deliveryDate;

    /**
     * @var time Start time of delivery gap
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "example"="09:00:00"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Time()
     * @ORM\Column(type="time")
     */
    private $deliveryStartTime;

    /**
     * @var time End time of delivery gap
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "example"="09:00:00"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @Assert\Time()
     * @ORM\Column(type="time")
     */
    private $deliveryEndTime;

    /**
     * @var User User who will delivery the order
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     * @ApiSubresource
     */
    private $driver;

    /**
     * @var User User who create the order
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="Address",
     *             "example"="/api/users/1"
     *         }
     *     }
     * )
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource
     */
    private $client;

    /**
     * @var Address Address of delivery
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="Address",
     *             "example"="/api/addresses/1"
     *         }
     *     }
     * )
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\Address")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource
     *
     */
    private $address;

    /**
     * @Assert\IsTrue(message="The password cannot match your username")
     */
    public function isCorrectHourGap()
    {
        $interval = date_diff($this->deliveryStartTime, $this->deliveryEndTime);
        $hours = $interval->format('%h');

        if($hours >= 1 && $hours <= 8) {
            return true;
        } else {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getDeliveryStartTime(): ?\DateTimeInterface
    {
        return $this->deliveryStartTime;
    }

    public function setDeliveryStartTime(\DateTimeInterface $deliveryStartTime): self
    {
        $this->deliveryStartTime = $deliveryStartTime;

        return $this;
    }

    public function getDeliveryEndTime(): ?\DateTimeInterface
    {
        return $this->deliveryEndTime;
    }

    public function setDeliveryEndTime(\DateTimeInterface $deliveryEndTime): self
    {
        $this->deliveryEndTime = $deliveryEndTime;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }
}

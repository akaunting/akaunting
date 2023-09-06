<?php

declare(strict_types=1);

namespace Sentry;

/**
 * This class stores the information about the authenticated user for a request.
 *
 * @see https://develop.sentry.dev/sdk/event-payloads/types/#user
 */
final class UserDataBag
{
    /**
     * @var string|int|null The unique ID of the user
     */
    private $id;

    /**
     * @var string|null The email address of the user
     */
    private $email;

    /**
     * @var string|null The IP of the user
     */
    private $ipAddress;

    /**
     * @var string|null The username of the user
     */
    private $username;

    /**
     * @var string|null the user segment, for apps that divide users in user segments
     */
    private $segment;

    /**
     * @var array<string, mixed> Additional data
     */
    private $metadata = [];

    /**
     * UserDataBag constructor.
     *
     * @param string|int|null $id
     */
    public function __construct(
        $id = null,
        ?string $email = null,
        ?string $ipAddress = null,
        ?string $username = null,
        ?string $segment = null
    ) {
        $this->setId($id);
        $this->setEmail($email);
        $this->setIpAddress($ipAddress);
        $this->setUsername($username);
        $this->setSegment($segment);
    }

    /**
     * Creates an instance of this object from a user ID.
     *
     * @param string|int $id The ID of the user
     */
    public static function createFromUserIdentifier($id): self
    {
        return new self($id);
    }

    /**
     * Creates an instance of this object from an IP address.
     *
     * @param string $ipAddress The IP address of the user
     */
    public static function createFromUserIpAddress(string $ipAddress): self
    {
        return new self(null, null, $ipAddress);
    }

    /**
     * Creates an instance of this object from the given data.
     *
     * @param array<string, mixed> $data The raw data
     */
    public static function createFromArray(array $data): self
    {
        $instance = new self();

        foreach ($data as $field => $value) {
            switch ($field) {
                case 'id':
                    $instance->setId($value);
                    break;
                case 'ip_address':
                    $instance->setIpAddress($value);
                    break;
                case 'email':
                    $instance->setEmail($value);
                    break;
                case 'username':
                    $instance->setUsername($value);
                    break;
                case 'segment':
                    $instance->setSegment($value);
                    break;
                default:
                    $instance->setMetadata($field, $value);
                    break;
            }
        }

        return $instance;
    }

    /**
     * Gets the ID of the user.
     *
     * @return string|int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the ID of the user.
     *
     * @param string|int|null $id The ID
     */
    public function setId($id): void
    {
        if (null !== $id && !\is_string($id) && !\is_int($id)) {
            throw new \UnexpectedValueException(sprintf('Expected an integer or string value for the $id argument. Got: "%s".', get_debug_type($id)));
        }

        $this->id = $id;
    }

    /**
     * Gets the username of the user.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Sets the username of the user.
     *
     * @param string|null $username The username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * Gets the email of the user.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email of the user.
     *
     * @param string|null $email The email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * Gets the segement of the user.
     */
    public function getSegment(): ?string
    {
        return $this->segment;
    }

    /**
     * Sets the segment of the user.
     *
     * @param string|null $segment The segment
     */
    public function setSegment(?string $segment): void
    {
        $this->segment = $segment;
    }

    /**
     * Gets the ip address of the user.
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * Sets the ip address of the user.
     *
     * @param string|null $ipAddress The ip address
     */
    public function setIpAddress(?string $ipAddress): void
    {
        if (null !== $ipAddress && false === filter_var($ipAddress, \FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException(sprintf('The "%s" value is not a valid IP address.', $ipAddress));
        }

        $this->ipAddress = $ipAddress;
    }

    /**
     * Gets additional metadata.
     *
     * @return array<string, mixed>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets the given field in the additional metadata.
     *
     * @param string $name  The name of the field
     * @param mixed  $value The value
     */
    public function setMetadata(string $name, $value): void
    {
        $this->metadata[$name] = $value;
    }

    /**
     * Removes the given field from the additional metadata.
     *
     * @param string $name The name of the field
     */
    public function removeMetadata(string $name): void
    {
        unset($this->metadata[$name]);
    }

    /**
     * Merges the given context with this one.
     *
     * @param UserDataBag $other The context to merge the data with
     *
     * @return $this
     */
    public function merge(self $other): self
    {
        $this->id = $other->id;
        $this->email = $other->email;
        $this->ipAddress = $other->ipAddress;
        $this->username = $other->username;
        $this->segment = $other->segment;
        $this->metadata = array_merge($this->metadata, $other->metadata);

        return $this;
    }
}

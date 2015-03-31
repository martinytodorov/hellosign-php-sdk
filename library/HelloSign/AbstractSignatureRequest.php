<?php
/**
 * HelloSign PHP SDK (https://github.com/HelloFax/hellosign-php-sdk/)
 */

namespace HelloSign;

/**
 * Requests to HelloSign will have common fields such as a request title,
 * subject, and message. This class centralizes those fields.
 */
abstract class AbstractSignatureRequest extends AbstractResource
{
    /**
     * The URL you want the signer redirected to after they successfully sign
     *
     * @var string
     */
    protected $signing_redirect_url = null;

    /**
     * The URL you want the requester redirected to after they successfully send their request
     *
     * @var string
     */
    protected $requesting_redirect_url = null;

    /**
     * An array of signers
     *
     * @var SignerList
     */
    protected $signers = null;

    /**
     * The email address of the initiator of the SignatureRequest
     *
     * @var string
     */
    protected $requester_email_address = null;

    /**
     * Whether this signature request uses text tags
     *
     * Text tags are macros in the document itself that signify form fields. Defaults to false.
     *
     * @var boolean
     */
    protected $use_text_tags = false;

    /**
     * If using text tags, white them out
     *
     * @var boolean
     */
    protected $hide_text_tags = false;

    /**
     * Custom key-value pairs that will be attached to the request.
     * @var array
     */
    protected $metadata = array();

    /**
     * Constructor
     *
     * @param  stdClass $response
     * @param  array $options
     * @ignore
     */
    public function __construct($response = null, $options = array())
    {
        $this->signers = new SignerList;

        parent::__construct($response, $options);
    }

    /**
     * @param  string $url
     * @return SignatureRequest
     * @ignore
     */
    public function setSigningRedirectUrl($url)
    {
        $this->signing_redirect_url = $url;
        return $this;
    }

    /**
     * @param  string $url
     * @return static
     * @ignore
     */
    public function setRequestingRedirectUrl($url)
    {
        $this->requesting_redirect_url = $url;
        return $this;
    }

    /**
     * Adds a signer to the signature request
     *
     * @param  mixed $email_or_signer
     * @param  string $name
     * @param  string $index
     * @return AbstractSignatureRequest
     */
    public function addSigner($email_or_signer, $name = null, $index = null)
    {
        $signer = ($email_or_signer instanceof Signer)
            ? $email_or_signer
            : new Signer(array(
                'name' => $name,
                'email_address' => $email_or_signer
            ));

        if (isset($index)) {
            $this->signers[$index] = $signer;
        } else {
            $this->signers[] = $signer;
        }

        return $this;
    }

    /**
     * @param  array $array
     * @param  array $options
     * @return AbstractSignatureRequest
     * @ignore
     */
    public function fromArray($array, $options = array())
    {
        array_key_exists('signers', $array) && $this->setSigners($array['signers']);

        !isset($options['except']) && $options['except'] = array();
        $options['except'][] = 'signers';

        return parent::fromArray($array, $options);
    }

    /**
     * @param  array $signers
     * @return AbstractSignatureRequest
     * @ignore
     */
    protected function setSigners($signers)
    {
        $this->signers->setCollection($signers);

        return $this;
    }

    /**
     * @param  string $email
     * @return SignatureRequest
     * @ignore
     */
    public function setRequesterEmailAddress($email)
    {
        $this->requester_email_address = $email;
        return $this;
    }

    /**
     *
     * Enable or disable text tags
     * @param boolean $use_text_tags
     */
    public function setUseTextTags($use_text_tags)
    {
    	$this->use_text_tags = $use_text_tags;
    	return $this;
    }

	/**
     *
     * Enable or disable hiding text tags
     * @param boolean $use_text_tags
     */
    public function setHideTextTags($hide_text_tags)
    {
    	$this->hide_text_tags = $hide_text_tags;
    	return $this;
    }

    /**
     * Set the metadata key to the provided value.
     * @param string $key
     * @param string $value
     */
    public function addMetadata($key, $value) {
        $this->metadata[$key] = $value;
    }

    /**
     * Get the current metadata value for the provided key.
     * @param string $key
     * @return string|NULL
     */
    public function getMetadata($key) {
    	if (!is_array($this->metadata)) {
            $this->metadata = json_decode(json_encode($this->metadata), true);
    	}
    	return (isset($this->metadata[$key])) ? $this->metadata[$key] : null;
    }
}

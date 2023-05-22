class Boston311Case {
    private $properties;

    public function __construct($caseData) {
        $this->properties = $caseData;
    }

    public function getProperty($propertyName) {
        return $this->properties[$propertyName] ?? null;
    }

    public function setProperty($propertyName, $value) {
        $this->properties[$propertyName] = $value;
    }
}

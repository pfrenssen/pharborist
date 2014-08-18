<?php
namespace Pharborist;

/**
 * A function parameter.
 */
class ParameterNode extends ParentNode {
  /**
   * @var Node
   */
  protected $typeHint;

  /**
   * @var TokenNode
   */
  protected $reference;

  /**
   * @var VariableNode
   */
  protected $name;

  /**
   * @var ExpressionNode
   */
  protected $value;

  /**
   * Create a parameter node.
   *
   * @param string $parameter_name
   *   Parameter name, eg. $parm
   * @return ParameterNode
   */
  public static function create($parameter_name) {
    $parameter_node = new ParameterNode();
    $parameter_node->append(new VariableNode(T_VARIABLE, $parameter_name));
    return $parameter_node;
  }

  /**
   * {@inheritdoc}
   */
  protected function childInserted(Node $node) {
    if ($node instanceof TokenNode) {
      if ($node->getType() === T_ARRAY || $node->getType() === T_CALLABLE) {
        $this->typeHint = $node;
      }
      elseif ($node->getType() === '&') {
        $this->reference = $node;
      }
      elseif ($node instanceof VariableNode) {
        $this->name = $node;
      }
      elseif ($node instanceof ExpressionNode) {
        $this->value = $node;
      }
    }
    elseif ($node instanceof NameNode) {
      $this->typeHint = $node;
    }
    elseif ($node instanceof ExpressionNode) {
      $this->value = $node;
    }
  }

  /**
   * @return Node
   */
  public function getTypeHint() {
    return $this->typeHint;
  }

  /**
   * @param string|Node $type_hint
   * @return $this
   */
  public function setTypeHint($type_hint) {
    if (is_string($type_hint)) {
      $type = $type_hint;
      $type_hint = new NameNode();
      $type_hint->append(Token::string($type));
    }
    if (isset($this->typeHint)) {
      $this->typeHint->replaceWith($type_hint);
    }
    else {
      $this->prepend([
        $type_hint,
        Token::space(),
      ]);
    }
    return $this;
  }

  /**
   * @return TokenNode
   */
  public function getReference() {
    return $this->reference;
  }

  /**
   * @param boolean $is_reference
   * @return $this
   */
  public function setReference($is_reference) {
    if ($is_reference) {
      if (!isset($this->reference)) {
        $this->name->before(Token::reference());
      }
    }
    else {
      if (isset($this->reference)) {
        $this->reference->remove();
      }
    }
    return $this;
  }

  /**
   * @return VariableNode
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   * @return $this
   */
  public function setName($name) {
    $this->name->setText($name);
    return $this;
  }

  /**
   * @return ExpressionNode
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @param ExpressionNode|NULL $node
   * @return $this
   */
  public function setValue($node) {
    if ($node === NULL) {
      if (isset($this->value)) {
        $this->value->previousUntil(Filter::isInstanceOf('\Pharborist\VariableNode'))->remove();
        $this->value->remove();
      }
    }
    else {
      if (isset($this->value)) {
        $this->value->replaceWith($node);
      }
      else {
        $this->append([
          Token::space(),
          Token::assign(),
          Token::space(),
          $node,
        ]);
      }
    }
    return $this;
  }
}

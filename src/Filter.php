<?php
namespace Pharborist;

/**
 * Factory for creating common callback filters.
 */
class Filter {
  /**
   * @param string $class_name
   * @return callable
   */
  public static function isInstanceOf($class_name) {
    return function ($node) use ($class_name) {
      return $node instanceof $class_name;
    };
  }

  /**
   * Callback to filter for specific function declaration.
   * @param string $function_name
   * @return callable
   */
  public static function isFunction($function_name) {
    return function ($node) use ($function_name) {
      if ($node instanceof FunctionDeclarationNode) {
        return $node->getName()->getText() === $function_name;
      }
      return FALSE;
    };
  }

  /**
   * Callback to filter for calls to a function.
   * @param string $function_name
   * @return callable
   */
  public static function isFunctionCall($function_name) {
    return function ($node) use ($function_name) {
      if ($node instanceof FunctionCallNode) {
        return $node->getName()->getText() === $function_name;
      }
      return FALSE;
    };
  }

  /**
   * Callback to filter for specific class declaration.
   * @param string $class_name
   * @return callable
   */
  public static function isClass($class_name) {
    return function ($node) use ($class_name) {
      if ($node instanceof ClassNode) {
        return $node->getName()->getText() === $class_name;
      }
      return FALSE;
    };
  }

  /**
   * Callback to filter for calls to a class method.
   * @param string $class_name
   * @param string $method_name
   * @return callable
   */
  public static function isClassMethodCall($class_name, $method_name) {
    return function ($node) use ($class_name, $method_name) {
      if ($node instanceof ClassMethodCallNode) {
        $class_matches = $node->getClassName()->getText() === $class_name;
        $method_matches = $node->getMethodName()->getText() === $method_name;
        return $class_matches && $method_matches;
      }
      return FALSE;
    };
  }

  /**
   * Callback to filter comments.
   * @param bool $include_doc_comment
   * @return callable
   */
  public static function isComment($include_doc_comment = TRUE) {
    if ($include_doc_comment) {
      return function ($node) {
        if ($node instanceof LineCommentBlockNode) {
          return TRUE;
        }
        elseif ($node instanceof CommentNode) {
          return !($node->parent() instanceof LineCommentBlockNode);
        }
        else {
          return FALSE;
        }
      };
    }
    else {
      return function ($node) {
        if ($node instanceof LineCommentBlockNode) {
          return TRUE;
        }
        elseif ($node instanceof DocCommentNode) {
          return FALSE;
        }
        elseif ($node instanceof CommentNode) {
          return !($node->parent() instanceof LineCommentBlockNode);
        }
        else {
          return FALSE;
        }
      };
    }
  }
}

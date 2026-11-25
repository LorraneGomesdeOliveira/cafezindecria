 <?php

 class produtoRepositorio
 {
    private PDO $pdo;
    
    /**
     * @param PDO $pdo  
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function normalizarPreco(string $preco): string
    {
      $preco = trim(str_replace(' ', '', $preco));

      if (str_contains($preco, ',')) {
        $preco = str_replace('.', '', $preco);
        $preco = str_replace(',', '.', $preco);
      }

      return $preco;
    }

    private function formarObjeto($dados)
    {  return new produtos($dados['id'],
            $dados['tipo'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem']);
       
    }
    public function opcoesCafe(): array
    { 
      $sql1 = "SELECT * FROM produtos WHERE tipo = 'Café' ORDER BY preco";
      $statement = $this->pdo -> query($sql1);
      $produtosCafe = $statement -> fetchAll(PDO::FETCH_ASSOC);

      $dadosCafe = array_map(function($cafe){
        return $this->formarObjeto($cafe);
      }, $produtosCafe);

    return $dadosCafe;
    }
    
    public function opcoesAlmoço(): array 
    {
     $sql2 = "SELECT * FROM produtos WHERE tipo = 'Almoço' ORDER BY preco";
      $statement = $this->pdo -> query($sql2);
      $produtosAlmoço = $statement -> fetchAll(PDO::FETCH_ASSOC);

      $dadosAlmoço = array_map(function($almoço){
        return $this->formarObjeto($almoço);
      }, $produtosAlmoço);  

    return $dadosAlmoço;
    }

    public function buscarTodos()
    {
     $sql = "SELECT * FROM  produtos  ORDER BY preco";
     $statement = $this->pdo -> query($sql);
     $dados = $statement -> fetchAll(PDO::FETCH_ASSOC);

      $todosOsDados = array_map(function($produto){
        return $this->formarObjeto($produto);
      }, $dados);

    return $todosOsDados;
    }
 
    public function deletar(int $id)
    {
        $sql = "DELETE FROM produtos WHERE id = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1,$id);
        $statement->execute();
    }

 
    public function salvar(produtos $produto)
    {
      $sql = "INSERT INTO produtos (tipo, nome, descricao, preco, imagem) VALUES (?, ?, ?, ?, ?)";
      $statement = $this->pdo->prepare($sql);
      $statement->bindValue(1, $produto->getTipo());
      $statement->bindValue(2, $produto->getNome());
      $statement->bindValue(3, $produto->getDescricao());
      $statement->bindValue(4, $this->normalizarPreco($produto->getPreco()));
      $statement->bindValue(5, $produto->getImagem());
      $statement->execute();
    }
    
    public function buscar(int $id)
    {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    
        $dados = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->formarObjeto($dados);

    }
    
    public function atualizar(produtos $produto)
    {
        $sql = "UPDATE produtos SET tipo = ?, nome = ?, descricao = ?, preco = ?, imagem = ? WHERE id = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $produto->getTipo());
        $statement->bindValue(2, $produto->getNome());
        $statement->bindValue(3, $produto->getDescricao());
        $statement->bindValue(4, $this->normalizarPreco($produto->getPreco()));
        $statement->bindValue(5, $produto->getImagem());
        $statement->bindValue(6, $produto->getId());
        $statement->execute();
    }



 } 
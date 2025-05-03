<?php
require_once 'classes/Pessoa.php';
require_once 'classes/Cidades.php';

class PessoaForm
{
    private $html;
    private $data;
    
    public function __construct()
    {
        $this->html = file_get_contents('html/form.html');
        $this->data = [
            'id'        => null,
            'nome'      => null,
            'endereco'  => null,
            'bairro'    => null,
            'telefone'  => null,
            'email'     => null,
            'id_cidade' => null
        ];
        
        $cidades = '';
        foreach (Cidades::all() as $cidade) {
            $cidades .= "<option value='{$cidade['id']}'>{$cidade['nome']}</option>";
        }
        $this->html = str_replace('{cidades}', $cidades, $this->html);
    }
    
    public function edit($param)
    {
        try
        {
            $id = (int) $param['id'];
            $this->data = Pessoa::find($id);
        }
        catch (Exception $e)
        {
            print $e->getMessage();
        }
    }
    
    public function save($param)
    {
        try
        {
            Pessoa::save($param);
            $this->data = $param;
            print "Pessoa salva com sucesso";
        }
        catch (Exception $e)
        {
            print $e->getMessage();
        }
    }
    
    public function show()
    {
        $this->html = str_replace('{id}',        (string) $this->data['id'],        $this->html);
        $this->html = str_replace('{nome}',      (string) $this->data['nome'],      $this->html);
        $this->html = str_replace('{endereco}',  (string) $this->data['endereco'],  $this->html);
        $this->html = str_replace('{bairro}',    (string) $this->data['bairro'],    $this->html);
        $this->html = str_replace('{telefone}',  (string) $this->data['telefone'],  $this->html);
        $this->html = str_replace('{email}',     (string) $this->data['email'],     $this->html);
        $this->html = str_replace('{id_cidade}', (string) $this->data['id_cidade'], $this->html);
        
        $this->html = str_replace("option value='{$this->data['id_cidade']}'",
                                  "option selected=1 value='{$this->data['id_cidade']}'",
                                  $this->html);
        print $this->html;
    }
}

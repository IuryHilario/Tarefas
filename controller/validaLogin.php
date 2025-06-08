<?php
session_start();
require_once '../conexao.php'; 

if(isset($_POST['email']) && isset($_POST['senha'])) {
    
    if(strlen($_POST['email']) == 0 || strlen($_POST['senha']) == 0) {
        header('location: ../index.php?login=erro');
        exit;
    } else {
        
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];

        $sql_consulta = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $mysqli->prepare($sql_consulta);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1) {
            $usuario_logado = $result->fetch_assoc();
            
            // Verificar se a senha está correta
            if(password_verify($senha, $usuario_logado['senha'])) {
                $_SESSION['usuario_id'] = $usuario_logado['id'];
                $_SESSION['usuario_nome'] = $usuario_logado['nome'];
                $_SESSION['usuario_email'] = $usuario_logado['email'];
                $_SESSION['usuario_grupo'] = $usuario_logado['grupo'];
                $_SESSION['autenticado'] = 'SIM';
                
                header('location: ../home.php');
                exit;
            } else {
                header('location: ../index.php?login=erro');
                exit;
            }
        } else {
            header('location: ../index.php?login=erro');
            exit;
        }
        
        $stmt->close();
    }
} else {
    header('location: ../index.php?login=erro');
    exit;
}
?>
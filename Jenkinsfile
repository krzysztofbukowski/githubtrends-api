pipeline {
  agent {
    docker {
      image 'composer:latest'
      args '-v $(pwd):/var/app composer install'
    }
    
  }
  stages {
    stage('Test') {
      steps {
        sh 'composer test'
      }
    }
  }
}
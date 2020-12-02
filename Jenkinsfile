pipeline {
  agent {
    docker {
      image 'composer:latest'
    }

  }
  stages {
    stage('Build') {
      steps {
        sh 'ls -lat'
        sh 'composer install'
      }
    }

    stage('Unit Test') {
      steps {
        sh 'vendor/bin/phpunit --log-junit logs/unitreport.xml tests/UnitTests'
      }
    }

  }
  post {
    always {
      junit testResults: 'logs/unitreport.xml'
    }
  }
}

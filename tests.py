import unittest
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium import webdriver 
from selenium.webdriver.chrome.options import Options

class PythonOrgSearch(unittest.TestCase):

    def setUp(self):
        chrome_options = Options()
        chrome_options.add_argument("--headless")
        self.driver = webdriver.Chrome(options=chrome_options)

    def test_search_in_python_org(self):
        driver = self.driver
        driver.get("ap/index.php")
        self.assertIn("Index", driver.title)
        assert "No results found." not in driver.page_source


    def tearDown(self):
        self.driver.close()
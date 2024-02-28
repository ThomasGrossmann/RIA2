import './App.css';
import './index.css';
import LanguageSwitcher from './components/LanguageSwitcher';
import Dropzone from './components/Dropzone';
import Parameters from './components/Parameters';
import AnalyzeButton from './components/AnalyzeButton';
import { useTranslation } from 'react-i18next';
import i18n from './i18n';

function App() {
  const { t } = useTranslation();
  return (
    <>
      <header className='flex flex-row justify-end p-4 top-0 sticky'>
        <LanguageSwitcher />
      </header>
      <main className='flex flex-col items-center mx-20 mb-10 gap-3'>
        <section data-testid="cypress-dragdrop" className='border border-red-400 p-12 w-full'>
          <Dropzone />
        </section>
        <section className='border border-green-400 p-12 w-full'>
          <Parameters />
        </section>
        <section className='border border-white-400 p-6 w-full'>
          <AnalyzeButton />
        </section>
        <section className='border border-yellow-400 p-24 w-full'>
          AnalyzeResults
        </section>
      </main>
    </>
  );
}

export default App;

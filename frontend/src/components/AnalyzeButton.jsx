import { useTranslation } from 'react-i18next';
import i18n from '../i18n';

const AnalyzeButton = () => {
  const { t } = useTranslation();

  return (
    <>
      <button className='bg-slate-500 hover:bg-slate-400 py-2 px-4 rounded w-full'>
        {t("analyze_button")}
      </button>
    </>
  );
}

export default AnalyzeButton;
import { useTranslation } from 'react-i18next';
import i18n from '../i18n';

const AnalyzeResults = ({ results }) => {
  const { t } = useTranslation();

  return (
    <>
      <div>
        <span>{t("analyze_results")}</span>
        <table id="results-table" className='w-full border border-slate-400'>
          <thead className='bg-slate-400'>
            <tr>
              <th>{t("labels")}</th>
              <th>{t("confidence")}</th>
            </tr>
          </thead>
          <tbody className='text-center'>
            {results.metrics && results.metrics.length > 0 && results.metrics.map((result, index) => (
              <tr key={index}>
                <td>{result.description}</td>
                <td>{result.confidenceLevel}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </>
  );
}

export default AnalyzeResults;
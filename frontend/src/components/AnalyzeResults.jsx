import { useTranslation } from 'react-i18next';
import i18n from '../i18n';

const AnalyzeResults = () => {
  const { t } = useTranslation();

  return (
    <>
      <table className='w-full border border-slate-400'>
        <thead className='bg-slate-400'>
          <tr>
            <th className='p-2'>Labels</th>
            <th className='p-2'>Confidence</th>
          </tr>
        </thead>
        <tbody className='text-center'>
          <tr>
            <td className='p-2'>Label 1</td>
            <td className='p-2'>90</td>
          </tr>
        </tbody>
      </table>
    </>
  );
}

export default AnalyzeResults;